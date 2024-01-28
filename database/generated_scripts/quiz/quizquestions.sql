create table quizquestions
(
    QuizQuestionID int auto_increment
        primary key,
    QuizID         int null,
    QuestionID     int null,
    constraint quiz_question
        foreign key (QuestionID) references questions (QuestionID),
    constraint quiz_quiz
        foreign key (QuizID) references quizzes (QuizID)
);

