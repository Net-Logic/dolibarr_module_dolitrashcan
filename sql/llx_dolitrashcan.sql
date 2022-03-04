-- Copyright (C) 2022  Frédéric France     <frederic.france@netlogic.fr>
--
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program.  If not, see https://www.gnu.org/licenses/.


CREATE TABLE llx_dolitrashcan
(
    rowid integer AUTO_INCREMENT PRIMARY KEY NOT NULL,
    tms TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    original_filename VARCHAR(255),
    original_created_at DATETIME,
    mimetype VARCHAR(255),
    deleted_at DATETIME,
    deleted_by integer,
    element VARCHAR(128) NULL,
    fk_element integer NULL,
    trashcan_filename VARCHAR(255)
) ENGINE=innodb;
