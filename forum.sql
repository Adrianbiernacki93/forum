create database forum; 
use forum;

create table topic(id int auto_increment primary key, heading varchar(255), content text);

insert into topic(heading,content) values ("Homepage","Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam ut aliquet nibh. Praesent vitae nisl id metus convallis consequat ut sit amet neque. Nam cursus ultrices risus ac laoreet. ");
insert into topic(heading,content) values ("Section1","Lorem ipsum dolor sit amet");
insert into topic(heading,content) values ("Section2","Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam ut aliquet nibh. Praesent vitae nisl id metus convallis consequat ut sit amet neque. ");

create table comments(id int primary key auto_increment,idsection int,user varchar(30), content text);

insert into comments(idsection,user,content) values (1,"Marcin","Lorem ipsum dolor sit amet");
insert into comments(idsection,user,content) values (1,"Kasia","Lorem ipsum dolor");


insert into comments(idsection,user,content) values (2,"Weronika","Lorem ipsum dolor sit amet");



