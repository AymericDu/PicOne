/*
Thibault Arloing
Aymeric Ducroquetz
*/

--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = PicOne, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

CREATE TABLE photos (
	url varchar(255) NOT NULL,
	size varchar(12) NOT NULL,
	mime_type varchar(20) NOT NULL,
	licence varchar(8) NOT NULL,
	thumbnail varchar(50) NOT NULL,
	author varchar(50) NOT NULL,
	url_author varchar(500) NOT NULL,
	title varchar(100) NOT NULL,
	categories varchar(200) NOT NULL,
	keywords varchar(200) NOT NULL,
	adding_date timestamp DEFAULT NOW(),
	PRIMARY KEY(url)
);
