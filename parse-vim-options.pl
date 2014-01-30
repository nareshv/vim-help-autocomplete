#!/usr/bin/env perl -wl

use strict;
use warnings;
use Data::Dumper;
use DBI;

my $dbh = DBI->connect('dbi:mysql:vim_db;host=127.0.0.1','root') or die "$DBI::errstr\n";
my $sql = "CREATE TABLE IF NOT EXISTS vim_options (id integer auto_increment primary key, option varchar(200) unique, short_desc text DEFAULT NULL, data_type varchar(64) default null, data_type_values varchar(200) default null, long_desc text DEFAULT NULL, is_global tinyint(1) DEFAULT NULL, local_to_window tinyint(1) DEFAULT NULL, local_to_buffer tinyint(1) DEFAULT NULL, not_in_vi tinyint(1) DEFAULT NULL, req_feature varchar(200) DEFAULT NULL, added_at datetime, updated_at datetime) charset=latin1";
$dbh->do($sql) || die "$!";
my $sth = $dbh->prepare( "INSERT into vim_options set option = ?, short_desc = ?, data_type = ?, data_type_values = ?, long_desc = ?, is_global = ?, local_to_window = ?, local_to_buffer = ?, not_in_vi = ?, req_feature = ?, added_at = now() on duplicate key update updated_at = now()");

# 
# Program to scan the options.txt.gz and generate
# documentation which is useful for building options
# online.
#
my $limit   = 50000;
my %options = ();
my $latest  = undef;
my $line    = "";
my $lc      = 0;
my $optdata = "";
my $all_lc  = 0;
my %tmpbuf  = ();
my $shdesc_ended = 0;

while(<STDIN>) {
	$line = $_;
	if ($line =~ /^('.*?)\t+([a-z].*)$/) {
		$all_lc = 1;
		$optdata= "";
		$latest = $1;
		#  Short, Long, IsGlobal, IsLocalFile, IsLocalBuffer, NotInVi, RequiredFeature
		$options{$latest} = [$2, "", 0, 0, 0, 0, ""];
		$lc = 1;
		next;
	}
	if ($lc == 1 and $line =~ /^\t{4,}/) {
		$options{$latest}[0] .= $_;
	} elsif ($lc == 1 and $line =~ /^\t{3,}/) {
		$all_lc++;
		# If the short description extends to 2nd line
		if ($all_lc == 2 and $line =~ /\)$/) {
			$options{$latest}[0] .= $_;
		} else {
			# Main options and their sub-options come here
			$optdata .= $_;
		}
	} elsif (defined $latest) {
		$all_lc++;
		$lc++;
		# parse the options dataset
		if (defined $latest and not defined $tmpbuf{$latest}) {
			# FIXME: Write this only once for each option
			$options{$latest}[1] .= "===========================\n";
			$optdata =~ s/^\s+//;
			$optdata =~ s/[\n\t]/ /g;
			$optdata =~ s/\s+/ /g;
			$optdata =~ s/\{/\n{/g;
			$options{$latest}[1] .= $optdata."\n";
			$options{$latest}[1] .= "===========================\n";
			$tmpbuf{$latest} = 1;
		}
		# Parse the optdata which has these options
		my @options = split(/\n/, $optdata);
		my %features = ();
		foreach my $aopt (@options) {
			if ($aopt =~ /^global/) {
				$options{$latest}[2] = 1;
			}
			if ($aopt =~ /^.*?local to window/i) {
				$options{$latest}[3] = 1;
			}
			if ($aopt =~ /^.*?local to buffer/i) {
				$options{$latest}[4] = 1;
			}
			if ($aopt =~ /^.*?not in vi/i) {
				$options{$latest}[5] = 1;
			}
			if ($aopt =~ /compiled/) {
				while($aopt =~ m/\|(.*?)\|/ig) {
					$features{$1} = 1;
				}
				$options{$latest}[6] = join(',', keys %features);
			}
		}
		# Save the other data into the long description
		$options{$latest}[1] .= $line;
	}
}

my $c = 0;
foreach my $opt (keys %options) {
	chomp(my $s = $options{$opt}[0]);
	$s =~ s/^\s+|\s+$//g;
	$s =~ s/\n/ /g;
	# Generate the datatype and its values
	my ($datatype, $datatype_values) = ($s =~ /^(.*?)\((.*?)\)/);
	$datatype =~ s/\s+//;
	$datatype_values =~ s/\t+/ /g;
	# Generate the CSV of option and its types
	my $opt_csv = $opt;
	$opt_csv =~ s/'//g;
	$opt_csv =~ s/^\s+|\s+$//;
	$opt_csv =~ s/\s+/,/g;
	$opt_csv =~ s/,+/,/g;
	# Sanitize the help data
	my $fulltext = $options{$opt}[1];
	$sth->execute($opt_csv, $s, $datatype, $datatype_values, $fulltext, $options{$opt}[2], $options{$opt}[3], $options{$opt}[4], $options{$opt}[5], $options{$opt}[6]) || die $!;
	print("========= $opt =========");
	printf("Option           : %s\n", $opt);
	printf("Short            : %s\n", $s);
	printf("Is Global        : %s\n", $options{$opt}[2]);
	printf("Is Local File    : %s\n", $options{$opt}[3]);
	printf("Is Local Buffer  : %s\n", $options{$opt}[3]);
	printf("Not In Vi        : %s\n", $options{$opt}[4]);
	printf("Feature          : %s\n", $options{$opt}[5]);
	print $fulltext;
	print("");
	#printf("%-16s\t%s\n", $opt, $options{$opt}[0]);
	#print $options{$opt}[1];
}
$sth->finish;
$dbh->disconnect;
exit;
