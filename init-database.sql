create if not exists table Users
(
    id int auto_increment primary key,
    username   longtext not null,
    first_name longtext not null,
    last_name  longtext not null
);