-- View Question by QuestionID,QuizID, UserID;
SELECT Q.QuestionID, Q.QuestionText, Qz.UserID, QQ.QuizID, Qz.QuizTitle
FROM Questions Q
         JOIN QuizQuestions QQ ON Q.QuestionID = QQ.QuestionID
         JOIN Quizzes Qz ON QQ.QuizID = Qz.QuizID
WHERE Q.QuestionID = 1 AND Qz.QuizID = 1 AND Qz.UserID = 1;

-- View Question List by Quiz ID
SELECT Q.QuestionID, Q.QuestionText
FROM Questions Q, quizzes Qz
         JOIN QuizQuestions QQ ON Q.QuestionID = QQ.QuestionID
WHERE QQ.QuizID = 1 AND Qz.UserID = 1;


-- Step 1: Insert the question into the Questions table
INSERT INTO Questions (QuestionText)
VALUES ('Your question text goes here');

-- Step 2: Insert the association with QuizID = 1 into the QuizQuestions table
-- Assuming you want to associate the last inserted question with QuizID = 1
INSERT INTO QuizQuestions (QuizID, QuestionID)
VALUES (1, LAST_INSERT_ID());
