
CREATE TABLE IF NOT EXISTS `waitlist` (
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY  (`customer_id`,`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
