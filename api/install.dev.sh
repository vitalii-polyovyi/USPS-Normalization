#!/bin/bash
set -ex

./vendor/bin/phinx migrate
./vendor/bin/phinx seed:run
