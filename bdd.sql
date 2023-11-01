CREATE TABLE post (
                      id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                      name VARCHAR(255) NOT NULL,
                      slug VARCHAR(255) NOT NULL,
                      content TEXT(65000) NOT NULL,
                      created_at DATETIME NOT NULL,
                      prix INTEGER NOT NULL,
                      marque_id INT UNSIGNED,  -- Nouvelle colonne pour la clé étrangère
                      PRIMARY KEY (id),
                      CONSTRAINT fk_marque
                          FOREIGN KEY (marque_id)
                              REFERENCES marque (id)
                              ON DELETE CASCADE
                              ON UPDATE RESTRICT
)

CREATE TABLE marque (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)

CREATE TABLE post_marque
(
    post_id   INT UNSIGNED NOT NULL,
    marque_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (post_id, marque_id),
    CONSTRAINT fk_post
        foreign key (post_id)
        references post (id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT ,
    CONSTRAINT fk_marque
        foreign key (marque_id)
            references marque (id)
            ON DELETE CASCADE
            ON UPDATE RESTRICT ,
)

CREATE TABLE user (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL ,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)