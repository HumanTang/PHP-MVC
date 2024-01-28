create table quizzes
(
    QuizID    int auto_increment
        primary key,
    UserID    int          null,
    QuizTitle varchar(100) not null,
    constraint quiz_userid
        foreign key (UserID) references users (UserID)
);

