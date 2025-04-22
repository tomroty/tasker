docker-compose down
docker-compose up -d db     # Start database first
sleep 10                    # Give database time to initialize
docker-compose up -d php    # Start PHP service