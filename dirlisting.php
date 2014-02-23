<?php
class dirlisting {
##
# WARNING:
# If you're going to be scanning 50+ directories make sure your PHPs memory setting is high enough,
# so that the script doesn't die on you.
##
	private $cwd;
	protected $Files = array();
	protected $Dirs = array();
	private $Settings = array(
		'dirs' => '', // Directory separator.
		'return' => false
	);
	
	function __construct($FS=false) {
		if ( $this->Settings['dirs'] == '' ) $this->getDirSep();
		$this->cwd = getcwd().$this->Settings['dirs'];
		if ( $FS ) $this->scanfld($this->cwd);
		if ( !isset($this->Settings['return']) ) $this->Settings['return'] = false;
	}
	private function getDirSep() {
		if ( isset($_SERVER['OS']) ) $this->Settings['dirs'] = '\\';
		else $this->Settings['dirs'] = '/';
	}
	public function chgsetting($Setting,$Variable) {
		if ( isset($this->Settings[$Setting]) ) {
			$this->Settings[$Setting] = $Variable;
			if ( $this->Settings[$Setting] == $Variable ) return(true);
		} else {
			return(false);
		}
	}
	public function clioutput($Folder=null,$Child=false,$Tabs=1) {
		if ( $this->Settings['return'] ) $ReturnArr = array();
		if ( $Folder == null ) {
			$S = "";
			for ( $i=0;$i<$Tabs;$i++ ) $S.="\t";
			if ( !$this->Settings['return'] ) echo "|*|=>{$this->cwd}:\n";
			else $ReturnArr[] = "|*|=>{$this->cwd}:\n";
			foreach ( $this->Files[$this->cwd] as $File ) {
				if ( !$this->Settings['return'] ) echo "|*|$S$File\n";
				else $ReturnArr[] = "|*|$S$File\n";
			}
			if ( is_array($this->Dirs[$this->cwd]) ) {
				$Tabs++;
				foreach ( $this->Dirs[$this->cwd] as $Dir ) {
					if ( !$this->Settings['return'] ) { $this->clioutput($this->cwd.$Dir,true,$Tabs); }
					else {
						$Returned = $this->clioutput($this->cwd.$Dir,true,$Tabs);
						foreach ( $Returned as $Line ) {
							$ReturnArr[] = $Line;
						}
					}
					
				}
			}
		} elseif ( is_array($this->Files[$Folder]) ) {
			$S = "";
			for ( $i=0;$i<$Tabs;$i++ ) $S.="\t";
			if ( !$this->Settings['return'] ) echo "|*|".substr($S,0,-1)."=>{$Folder}:\n";
			else $ReturnArr[] = "|*|".substr($S,0,-1)."=>{$Folder}:\n";
			if ( count($this->Files[$Folder]) > 0 ) {
				foreach ( $this->Files[$Folder] as $File ) {
					if ( !$this->Settings['return'] ) echo "|*|$S$File\n";
					else $ReturnArr[] = "|*|$S$File\n";
				}
			}
			if ( is_array($this->Dirs[$Folder]) ) {
				$Tabs++;
				foreach ( $this->Dirs[$Folder] as $Dir ) {
					if ( !$this->Settings['return'] ) { $this->clioutput($Folder.$Dir,true,$Tabs); }
					else {
						$Returned = $this->clioutput($Folder.$Dir,true,$Tabs);
						foreach ( $Returned as $Line ) {
							$ReturnArr[] = $Line;
						}
					}
				}
			}
		}
		if ( $this->Settings['return'] && isset($ReturnArr) ) return($ReturnArr);
	}
	public function htmloutput($Folder=null,$Child=false) {
		if ( $this->Settings['return'] ) $ReturnArr = array();
		if ( $Folder == null ) {
			if ( !$this->Settings['return'] ) {
				echo "Directory: {$this->cwd}<br /><br />";
				echo "<ul>";
			} else {
				$ReturnArr[] = "Directory: {$this->cwd}<br /><br />";
				$ReturnArr[] = "<ul>";
			}
			foreach ( $this->Files[$this->cwd] as $File ) {
				if ( !$this->Settings['return'] ) echo "<li>$File</li>";
				else $ReturnArr[] = "<li>$File</li>";
			}
			if ( !$this->Settings['return'] ) echo "</ul>";
			else $ReturnArr[] = "</ul>";
			if ( is_array($this->Dirs[$this->cwd]) ) {
				foreach ( $this->Dirs[$this->cwd] as $Dir ) {
					if ( !$this->Settings['return'] ) { $this->htmloutput($this->cwd.$Dir,true); }
					else {
						$Returned = $this->htmloutput($this->cwd.$Dir,true);
						foreach ( $Returned as $Line ) {
							$ReturnArr[] = $Line;
						}
					}
				}
			}
		} elseif ( is_array($this->Files[$Folder]) ) {
			if ( !$this->Settings['return'] ) echo "Directory: $Folder<br /><br />";
			else $ReturnArr[] = "Directory: $Folder<br /><br />";
			if ( count($this->Files[$Folder]) > 0 ) {
				if ( !$this->Settings['return'] ) echo "<ul>";
				else $ReturnArr[] = "<ul>";
				foreach ( $this->Files[$Folder] as $File ) {
					if ( !$this->Settings['return'] ) echo "<li>$File</li>";
					else $ReturnArr[] = "<li>$File</li>";
				}
				if ( $Child ) {
					if ( !$this->Settings['return'] ) echo "</ul>";
					else $ReturnArr[] = "</ul>";
				}
				if ( is_array($this->Dirs[$Folder]) ) {
					foreach ( $this->Dirs[$Folder] as $Dir ) {
						if ( @is_array($this->Files[$Folder.$Dir]) ) $NumFiles = count($this->Files[$Folder.$Dir]);
						if ( !$Child ) {
							if ( $NumFiles > 0 ) {
								if ( !$this->Settings['return'] ) echo "<li>* $Dir <b>[$NumFiles File(s)]</b></li>";
								else $ReturnArr[] = "<li>* $Dir <b>[$NumFiles File(s)]</b></li>";
							} else {
								if ( !$this->Settings['return'] ) echo "<li>* $Dir</li>";
								else $ReturnArr[] = "<li>* $Dir</li>";
							}
						} else {
							if ( $NumFiles > 0 ) {
								if ( !$this->Settings['return'] ) { $this->htmloutput($Folder.$Dir,true); }
								else {
									$Returned = $this->htmloutput($Folder.$Dir,true);
									foreach ( $Returned as $Line ) {
										$ReturnArr[] = $Line;
									}
								}
							}
						}
					}
				}
				if ( !$Child ) {
					if ( !$this->Settings['return'] ) {
						echo "</ul>";
						echo "* Directory";
					} else {
						$ReturnArr[] = "</ul>";
						$ReturnArr[] = "* Directory";
					}
				}
			} else {
				if ( !$this->Settings['return'] ) echo "<ul><li>Empty Folder</li></ul>";
				else $ReturnArr[] = "<ul><li>Empty Folder</li></ul>";
			}
		}
		if ( $this->Settings['return'] && isset($ReturnArr) ) return($ReturnArr);
	}
	public function arrayoutput($Folder=null) {
		if ( $Folder==null ) return($this->Files);
		elseif ( is_array($this->Files[$Folder]) ) return($this->Files[$Folder]);
		else return(NULL);
	}
	public function scanfld( $Folder, $Recur=false, $AvoidEx=array(), $Child=false ) {
		if ( !is_dir($Folder) ) return(false);
		if ( $Folder[strlen($Folder)-1] != $this->Settings['dirs'] ) $Folder.= $this->Settings['dirs'] ;
		if ( !$Child && $this->cwd != $Folder ) $this->cwd = $Folder;
		$Array = @scandir($Folder);
		$FileArray = array();
		$DirArray = array();
		if ( is_array($Array) ) {
			foreach ( $Array as $Arr ) {
				$FNParts = explode('.',$Arr);
				$Ex = $FNParts[count($FNParts)-1];
				if ( !in_array($Ex,$AvoidEx) && $Arr != '.' && $Arr != '..' ) {
					if ( is_dir($Folder.$Arr) && $Recur ) {
						$DirArray[] = $Arr.$this->Settings['dirs'];
						$this->scanfld($Folder.$Arr, $Recur, $AvoidEx, true);
					} else {
						$FileArray[] = $Arr;
					}
				}
			}
		}
		$this->Dirs[$Folder] = $DirArray;
		$this->Files[$Folder] = $FileArray;
	}
}

$DR = new dirlisting();

$DR->scanfld('E:\Media\Films', true);

$DR->clioutput();

?>
