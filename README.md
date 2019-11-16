# task

CREATE TABLE `task` (
  `i` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `body` text NOT NULL,
  `done` tinyint(4) DEFAULT NULL,
  `red` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
