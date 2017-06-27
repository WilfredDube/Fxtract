CREATE TABLE `members` (
  `memberID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `active` varchar(255) NOT NULL,
  `resetToken` varchar(255) DEFAULT NULL,
  `resetComplete` varchar(3) DEFAULT 'No',
  PRIMARY KEY (`memberID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `files` (
`fileid` INTEGER NOT NULL AUTO_INCREMENT,
`fileuserid` INTEGER NOT NULL,
`filename` VARCHAR(50) NOT NULL unique,
`filetype` VARCHAR(20) NOT NULL unique,
`filesize` INTEGER NOT NULL,
`filecaption` VARCHAR(255) NOT NULL DEFAULT "",
`filemodelmaterial` INTEGER NOT NULL,
`filemodelunits` VARCHAR(10) NULL,
`fileuploaddate` DATE NOT NULL,
PRIMARY KEY (`fileid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `bends` (
`b_id` INTEGER NOT NULL unique,
`file_id` INTEGER NOT NULL,
`bend_id` INTEGER NOT NULL,
`face1_id` INTEGER NOT NULL,
`face2_id` INTEGER NOT NULL,
`angle` INTEGER NOT NULL,
`bend_loop_id` INTEGER NOT NULL,
`bend_length` FLOAT NOT NULL,
`bend_thickness` FLOAT NOT NULL,
`bend_radius` FLOAT NOT NULL,
`bend_height` FLOAT NOT NULL,
`bending_force` FLOAT NOT NULL,
PRIMARY KEY (`b_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `tool_library` (
`toolid` INTEGER NOT NULL AUTO_INCREMENT,
`toolname` VARCHAR(50) NOT NULL unique,
`toolangles` VARCHAR(200) NOT NULL,
`toolcaption` VARCHAR(255) NOT NULL DEFAULT "",
PRIMARY KEY (`toolid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `tstrength` (
`m_id` INTEGER NOT NULL AUTO_INCREMENT,
`material` varchar(255) NOT NULL UNIQUE,
`tensile_strength` FLOAT NOT NULL,
PRIMARY KEY (`m_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `tstrength` (material, tensile_strength) VALUES ('Steel, 0.2% Carbon, hot rolled', 415);
INSERT INTO `tstrength` (material, tensile_strength) VALUES ('Steel, 0.2% Carbon, cold rolled', 550);
INSERT INTO `tstrength` (material, tensile_strength) VALUES ('Steel, 0.8% Carbon, hot rolled', 825);
INSERT INTO `tstrength` (material, tensile_strength) VALUES ('Stainless Steel', 622);
INSERT INTO `tstrength` (material, tensile_strength) VALUES ('Aluminum', 495);
INSERT INTO `tstrength` (material, tensile_strength) VALUES ('Titanium Alloy', 1069);
INSERT INTO `tstrength` (material, tensile_strength) VALUES ('Brass Annealed', 331);
