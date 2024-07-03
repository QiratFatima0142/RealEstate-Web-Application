-- =====================================================================
-- EstateEase - schema.sql
-- Real Estate Web Application (Web Application Development, UCP 2024)
--
-- Enhanced from the original July 2024 coursework submission:
--   - Switched passwords from integer to bcrypt hash (VARCHAR 255).
--   - Snake-cased columns (userid -> user_id, totalAmount -> total_amount).
--   - Added NOT NULL, UNIQUE, and CHECK constraints.
--   - Added created_at timestamps to every table.
--   - Added contact_message table for the contact form.
--   - Replaced TEXT price field with INT(unsigned) to allow aggregation.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS realstate
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE realstate;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS contact_message;
DROP TABLE IF EXISTS soldproperty;
DROP TABLE IF EXISTS purchase;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- users
--   Accounts for agents/owners who log in to the portal.
-- ---------------------------------------------------------------------
CREATE TABLE users (
    id             INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    email          VARCHAR(160)        NOT NULL,
    password_hash  VARCHAR(255)        NOT NULL,
    first_name     VARCHAR(60)         NOT NULL,
    last_name      VARCHAR(60)         NOT NULL,
    created_at     TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    CONSTRAINT chk_users_email CHECK (email LIKE '%@%')
) ENGINE = InnoDB;

-- ---------------------------------------------------------------------
-- purchase
--   Every property a user buys (aka "purchased properties").
-- ---------------------------------------------------------------------
CREATE TABLE purchase (
    id             INT UNSIGNED        NOT NULL AUTO_INCREMENT,
    user_id        INT UNSIGNED        NOT NULL,
    name           VARCHAR(120)        NOT NULL,
    total_amount   DECIMAL(14, 2)      NOT NULL,
    area_sqm       INT UNSIGNED        NOT NULL,
    purchase_date  DATE                NOT NULL,
    photo          VARCHAR(255)        DEFAULT NULL,
    created_at     TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    KEY idx_purchase_user (user_id),
    KEY idx_purchase_date (purchase_date),

    CONSTRAINT fk_purchase_user
        FOREIGN KEY (user_id) REFERENCES users (id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT chk_purchase_amount CHECK (total_amount > 0),
    CONSTRAINT chk_purchase_area   CHECK (area_sqm     > 0)
) ENGINE = InnoDB;

-- ---------------------------------------------------------------------
-- soldproperty
--   A sale recorded against a purchased property. One-to-one with
--   purchase so a property can be sold exactly once.
-- ---------------------------------------------------------------------
CREATE TABLE soldproperty (
    id               INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    purchase_id      INT UNSIGNED      NOT NULL,
    sold_date        DATE              NOT NULL,
    total_amount     DECIMAL(14, 2)    NOT NULL,
    received_amount  DECIMAL(14, 2)    NOT NULL DEFAULT 0,
    next_date        DATE              DEFAULT NULL,
    created_at       TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_soldproperty_purchase (purchase_id),
    KEY idx_soldproperty_date (sold_date),

    CONSTRAINT fk_soldproperty_purchase
        FOREIGN KEY (purchase_id) REFERENCES purchase (id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT chk_soldproperty_total    CHECK (total_amount    > 0),
    CONSTRAINT chk_soldproperty_received CHECK (received_amount >= 0 AND received_amount <= total_amount)
) ENGINE = InnoDB;

-- ---------------------------------------------------------------------
-- contact_message
--   Submissions from the public contact form.
-- ---------------------------------------------------------------------
CREATE TABLE contact_message (
    id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(160)  NOT NULL,
    message    TEXT          NOT NULL,
    created_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    KEY idx_contact_created (created_at)
) ENGINE = InnoDB;
