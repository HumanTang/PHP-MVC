SELECT questions.QuestionID, QuestionText
FROM Questions
         Join quizquestions on questions.QuestionID = quizquestions.QuestionID
WHERE quizquestions.QuizID = :quizid;

SELECT questions.QuestionID, QuestionText,AnswerID,AnswerText
FROM Questions
         Join answers on answers.QuestionID = questions.QuestionID
WHERE answers.QuestionID = :questionid;


SELECT questions.QuestionID, QuestionText, AnswerID, AnswerText, IsCorrect
FROM Questions
         Join answers on answers.QuestionID = questions.QuestionID
WHERE answers.QuestionID in (SELECT questions.QuestionID
                             FROM Questions
                                      Join quizquestions on questions.QuestionID = quizquestions.QuestionID
                             WHERE quizquestions.QuizID = :quizid);



SELECT questions.QuestionID, QuestionText, answers.AnswerText
FROM Questions
         Join answers on questions.QuestionID = answers.QuestionID
WHERE Questions.QuestionID = :id;