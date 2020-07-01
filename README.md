# konkursdb-viewer

This is the web viewer for the Konkursdatenbank Deutsches Kaiserreich (1879-1914).

## Erprobte Kompatibilität
- PHP 7.2.31 und 7.4.7
- Apache 2.4.43
- MariaDB 10.4.13
- Bisher Windows 7 als Betriebssystem genutzt!

## Installation
- Datenbank-Zugangsdaten in config.php anpassen
- memory_limit und time_limit in config.php ggf. anpassen (empfohlene Werte für große Abfragen voreingestellt)
- php.ini: include_path = "."
- php.ini: max_input_vars = 65536
- Webserver: index.php als Startseite festlegen (DirectoryIndex bei Apache)
- Für das Verzeichnis templates_c sind Schreibrechte nötig. Im Produktivbetrieb kann in config.php das Caching der Template-Engine aktiviert werden. In diesem Fall sind auch Schreibrechte für das Verzeichnis cache erforderlich.

## License

Copyright 2020 Christoph Kling

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
