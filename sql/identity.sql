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

CREATE TABLE identity (
	login varchar(30) NOT NULL,
	name varchar(30) NOT NULL,
	firstname varchar(30) NOT NULL,
	password varchar(200) NOT NULL,
	PRIMARY KEY (login)
);
