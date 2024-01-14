CREATE DATABASE quiz;

START TRANSACTION;
-- Table for User Information
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(100) NOT NULL
);

-- Table for Quizzes
CREATE TABLE Quizzes (
    QuizID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT, -- Foreign key referencing Users table
    QuizTitle VARCHAR(100) NOT NULL
);

-- Table for Questions
CREATE TABLE Questions (
    QuestionID INT AUTO_INCREMENT PRIMARY KEY,
    QuestionText TEXT NOT NULL
);

-- Table for QuizQuestions (Associating Questions with Quizzes)
CREATE TABLE QuizQuestions (
    QuizQuestionID INT AUTO_INCREMENT PRIMARY KEY,
    QuizID INT, -- Foreign key referencing Quizzes table
    QuestionID INT -- Foreign key referencing Questions table
);

-- Table for Answers
CREATE TABLE Answers (
    AnswerID INT AUTO_INCREMENT PRIMARY KEY,
    QuestionID INT, -- Foreign key referencing Questions table
    AnswerText VARCHAR(255) NOT NULL,
    IsCorrect BOOLEAN NOT NULL
);



ALTER TABLE quizzes
    ADD CONSTRAINT quiz_userid
        FOREIGN KEY (userid)
            REFERENCES users(userid);

ALTER TABLE quizquestions
    ADD CONSTRAINT quiz_question
        FOREIGN KEY (questionid)
            REFERENCES questions(questionid);


ALTER TABLE quizquestions
    ADD CONSTRAINT quiz_quiz
        FOREIGN KEY (quizid)
            REFERENCES quizzes(quizid);

ALTER TABLE answers
    ADD CONSTRAINT answer_question
        FOREIGN KEY (questionid)
            REFERENCES questions(questionid) ;
COMMIT;