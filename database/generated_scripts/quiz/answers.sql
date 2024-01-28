create table answers
(
    AnswerID   int auto_increment
        primary key,
    QuestionID int          null,
    AnswerText varchar(255) not null,
    IsCorrect  tinyint(1)   not null,
    constraint answer_question
        foreign key (QuestionID) references questions (QuestionID)
);

