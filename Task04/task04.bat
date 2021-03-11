#!/bin/bash
chcp 65001

sqlite3 movies_rating.db < db_init.sql

echo 1. Найти все драмы, выпущенные после 2005 года, которые понравились женщинам (оценка не ниже 4.5). Для каждого фильма в этом списке вывести название, год выпуска и количество таких оценок.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT DISTINCT movies.title AS 'Title', movies.year AS 'Year', COUNT(*) AS 'The number of ratings not less than 4.5' FROM movies INNER JOIN(SELECT * FROM ratings WHERE ratings.rating>=4.5) ratings ON movies.id = ratings.movie_id INNER JOIN(SELECT * FROM users WHERE users.gender='female') users ON ratings.user_id=users.id WHERE movies.year>2005 AND INSTR(movies.genres, 'Drama')>0 GROUP BY movies.title;"
echo " "

echo 2. Провести анализ востребованности ресурса - вывести количество пользователей, регистрировавшихся на сайте в каждом году. Найти, в каких годах регистрировалось больше всего и меньше всего пользователей.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "CREATE VIEW Year AS SELECT SUBSTR(users.register_date,1,4) AS 'Register_year', COUNT(users.id) AS 'Number' FROM users GROUP BY SUBSTR(users.register_date,1,4); SELECT * FROM Year;"
sqlite3 movies_rating.db -box -echo "SELECT Register_year, Number FROM(SELECT *, MAX(Number)OVER() AS 'Active', MIN(Number)OVER() AS 'Inactive' FROM Year) WHERE Number=Active OR Number=Inactive; DROP VIEW Year;"
echo " "

echo 3. Найти все пары пользователей, оценивших один и тот же фильм. Устранить дубликаты, проверить отсутствие пар с самим собой. Для каждой пары должны быть указаны имена пользователей и название фильма, который они ценили.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT DISTINCT movies.title, user1.name, user2.name FROM ratings A, ratings B INNER JOIN movies ON A.movie_id = movies.id INNER JOIN users user1 ON user1.id=A.user_id INNER JOIN users user2 ON user2.id=B.user_id WHERE A.movie_id = B.movie_id AND A.user_id < B.user_id ORDER BY movies.title LIMIT 100;"
echo " "

echo 4. Найти 10 самых старых оценок от разных пользователей, вывести названия фильмов, имена пользователей, оценку, дату отзыва в формате ГГГГ-ММ-ДД.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "CREATE VIEW Old_Rating AS SELECT ratings.user_id AS 'userID', ratings.movie_id AS 'movieID', ratings.rating AS 'Rating', MIN(DATE(ratings.timestamp,'unixepoch')) AS 'Date' FROM ratings GROUP BY ratings.user_id ORDER BY timestamp ASC;"
sqlite3 movies_rating.db -box -echo "SELECT movies.title AS 'Movie',Rating, users.name AS 'User', Date FROM movies, users, Old_Rating WHERE movies.id = movieID AND users.id = userID LIMIT 10; DROP VIEW Old_Rating;"
echo " "

echo 5. Вывести в одном списке все фильмы с максимальным средним рейтингом и все фильмы с минимальным средним рейтингом. Общий список отсортировать по году выпуска и названию фильма. В зависимости от рейтинга в колонке "Рекомендуем" для фильмов должно быть написано "Да" или "Нет".
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "CREATE VIEW Popular AS SELECT movies.title AS 'Movie', movies.year AS 'Year',Rating, Number_of_ratings FROM movies INNER JOIN(SELECT ratings.movie_id, AVG(ratings.rating) as 'Rating', COUNT(ratings.user_id) AS 'Number_of_ratings' FROM ratings GROUP BY ratings.movie_id) ratings ON ratings.movie_id = movies.id;"
sqlite3 movies_rating.db -box -echo "SELECT Movie, Year, Rating, CASE WHEN Rating=Max  THEN 'Yes' ELSE 'No' END AS 'Рекомендуем' FROM(SELECT *, MAX(Rating)OVER() AS 'Max', MIN(Rating)OVER() AS 'Min' FROM Popular) WHERE Rating = Max OR Rating = Min ORDER BY Year, Movie;"
echo " "

echo 6. Вычислить количество оценок и среднюю оценку, которую дали фильмам пользователи-мужчины в период с 2011 по 2014 год.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "CREATE VIEW Ratings2 AS SELECT ratings.rating AS 'Ratings',DATE(ratings.timestamp,'unixepoch') AS 'Date' FROM ratings INNER JOIN(SELECT users.id, users.gender FROM users WHERE users.gender='male') users ON ratings.user_id=users.id WHERE DATE(ratings.timestamp,'unixepoch') BETWEEN '2011' AND '2014' ORDER BY ratings.timestamp;"
sqlite3 movies_rating.db -box -echo "SELECT COUNT(*) AS 'Number of ratings given by men', AVG(Ratings) as 'Average rating' FROM Ratings2; DROP VIEW Ratings2"
echo " "

echo 7. Составить список фильмов с указанием средней оценки и количества пользователей, которые их оценили. Полученный список отсортировать по году выпуска и названиям фильмов. В списке оставить первые 20 записей.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT Year, Movie, Rating, Number_of_ratings FROM Popular WHERE Year<>0 ORDER BY Year, Movie LIMIT 20;"
echo " "

echo 8. Определить самый распространенный жанр фильма и количество фильмов в этом жанре.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "CREATE VIEW Genres AS WITH T(id,genre, str) AS(SELECT id, NULL, genres FROM movies UNION ALL SELECT id, CASE WHEN INSTR(str,'|') = 0 THEN str ELSE SUBSTR(str,1,INSTR(str,'|')-1) END, CASE WHEN INSTR(str,'|')=0 THEN NULL ELSE SUBSTR(str,INSTR(str,'|')+1) END FROM T WHERE str IS NOT NULL ORDER BY id) SELECT genre AS 'Genre', COUNT(id) AS 'Number' FROM T WHERE genre IS NOT NULL GROUP BY genre;" #разделение записи для каждого фильма на жанры#
sqlite3 movies_rating.db -box -echo "SELECT Genre AS 'The most widespread genre', MAX(Number) AS 'Number of films' FROM Genres; DROP VIEW Genres; "
echo " "