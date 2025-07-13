#!/bin/bash
# Start the PHP development server
cd /opt/render/project/src
exec php -S 0.0.0.0:$PORT -t public
