#таблица авторы

CREATE TABLE `authors` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`)
) COLLATE='utf8_unicode_ci';

#таблица книги

CREATE TABLE `books` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255) NOT NULL,
	`year` INT(4) NOT NULL,
	PRIMARY KEY (`id`)
) COLLATE='utf8_unicode_ci';

#таблица сопоставления

CREATE TABLE `authors_books` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`author_id` INT NOT NULL DEFAULT '0',
	`book_id` INT NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) COLLATE='utf8_unicode_ci';

#внешние ключи

ALTER TABLE `authors_books`
	ADD CONSTRAINT `FK_authors_books_authors` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
	ADD CONSTRAINT `FK_authors_books_books` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);

#Написать SQL который вернет список книг, написанный ровно 3-мя соавторами.

SELECT
	b.title,
    count(a.id) as authors_count
FROM
	books b
	inner join authors_books ab on ab.book_id = b.id
	inner join authors a on a.id = ab.author_id
GROUP BY
	b.id
HAVING
	count(authors_count) = 3