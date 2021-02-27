#!/bin/bash
chcp 65001

sqlite3 movies_rating.db < db_init.sql

echo 1.Составить список фильмов, имеющих хотя бы одну оценку. Список фильмов отсортировать по году выпуска и по названиям. В списке оставить первые 10 фильмов.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT DISTINCT movies.title, movies.year FROM movies INNER JOIN ratings ON movies.id = ratings.movie_id WHERE movies.year IS NOT NULL GROUP BY movies.id ORDER BY movies.year, movies.title LIMIT 10;"
echo " "

echo 2.Вывести список всех пользователей, фамилии (не имена!) которых начинаются на букву 'A'. Полученный список отсортировать по дате регистрации. В списке оставить первых 5 пользователей.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT users.nsme, regisrer_date FROM users WHERE users.name LIKE '%% A%%' ORDER BY users.register_date LIMIT 5;"
echo " "

echo 3. Написать запрос, возвращающий информацию о рейтингах в более читаемом формате: имя и фамилия эксперта, название фильма, год выпуска, оценка и дата оценки в формате ГГГГ-ММ-ДД. Отсортировать данные по имени эксперта, затем названию фильма и оценке. В списке оставить первые 50 записей.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT users.name, movies.title, movies.year, ratings.rating, DATE(ratings.timestamp,'unixepoch') AS 'ratings date' FROM users INNER JOIN ratings ON users.id = ratings.user_id INNER JOIN movies ON ratings.movie_id = movies.id ORDER BY users.name ASC, movies.title ASC,ratings.rating LIMIT 50;"
echo " "

echo 4. Вывести список фильмов с указанием тегов, которые были им присвоены пользователями. Сортировать по году выпуска, затем по названию фильма, затем по тегу. В списке оставить первые 40 записей.
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT movies.title, tags.tag, users.name FROM movies INNER JOIN tags ON movies.id = tags.movie_id INNER JOIN users ON users.id = tags.users_id WHERE movies.year IS NOT NULL ORDER BY  movies.year, movies.title, tags.tag LIMIT 40;"
echo " "

echo 5. Вывести список самых свежих фильмов. В список должны войти все фильмы последнего года выпуска, имеющиеся в базе данных. Запрос должен быть универсальным, не зависящим от исходных данных (нужный год выпуска должен определяться в запросе, а не жестко задаваться).
echo --------------------------------------------------
sqlite3 movies_rating.db -box -echo "SELECT movies.title, movies.year FROM movies WHERE year=(SELECT MAX(year) FROM movies);"
echo " "
