create table users
(
    UserID   int auto_increment
        primary key,
    Username varchar(50)  not null,
    Password varchar(255) not null,
    Email    varchar(100) not null
);

