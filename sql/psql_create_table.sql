drop table IF EXISTS links;
drop table IF EXISTS nodes;
drop table IF EXISTS nodes_old;
drop table IF EXISTS users;

drop sequence IF EXISTS links_seq;
drop sequence IF EXISTS nodes_seq;
drop sequence IF EXISTS nodes_old_seq;
drop sequence IF EXISTS users_seq;

create sequence links_seq increment by 1 no maxvalue start with 1;
create sequence nodes_seq increment by 1 no maxvalue start with 1;
create sequence nodes_old_seq increment by 1 no maxvalue start with 1;
create sequence users_seq increment by 1 no maxvalue start with 1;

create table links (
    id numeric(11) not null default nextval('links_seq'),
    node1 numeric(11) not null default 0,
    node2 numeric(11) not null default 0,
    type varchar(4) not null default 'wifi',
    quality numeric(11) not null default 0,
    network varchar(8) not null default 'roma',
    primary key(id)
);

CREATE TABLE nodes (
  id numeric(11) NOT NULL default nextval('nodes_seq'),
  createdOn timestamp NOT NULL default CURRENT_TIMESTAMP,
  status numeric(11) NOT NULL default '0',
  adminHash varchar(100) NOT NULL default '',
  lng text,
  lat text,
  elevation text,
  userRealName varchar(100) NOT NULL default '',
  userEmail varchar(100) NOT NULL default '',
  nodeName varchar(100) NOT NULL default '',
  nodeDescription text NOT NULL,
  nodeIP inet NOT NULL,
  userWebsite text,
  userJabber text,
  userEmailPublish boolean default NULL,
  streetAddress text,
  PRIMARY KEY (id),
  UNIQUE (id)
);


create TABLe nodes_old (
  id numeric(11) NOT NULL default nextval('nodes_old_seq'),
  createdOn timestamp NOT NULL default CURRENT_TIMESTAMP,
  status numeric(11) NOT NULL default '0',
  adminHash varchar(100) NOT NULL default '',
  lat double precision NOT NULL default '0',
  lng double precision NOT NULL default '0',
  userRealName varchar(100) NOT NULL default '',
  userEmail varchar(100) NOT NULL default '',
  nodeName varchar(100) NOT NULL default '',
  nodeDescription varchar(100) NOT NULL default '',
  PRIMARY KEY  (id)
);


CREATE TABLE users (
  id numeric(11) NOT NULL default nextval('users_seq'),
  username varchar(30) NOT NULL default '',
  password varchar(60) NOT NULL default '',
  email varchar(30) NOT NULL default '',
  realname varchar(60) NOT NULL default '',
  access boolean NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE (username)
);
