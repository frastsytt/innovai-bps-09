#!/bin/bash

# Step 1: Get the machine's hostname
HOSTNAME=$(hostname)
BACKUP_METADATA=~/docker_backup_metadata.txt

# Step 2: Ensure Docker login
docker logout
read -p "Enter your Docker Hub username: " DOCKER_USER
read -s -p "Enter your Docker Hub password or PAT: " DOCKER_PASS
echo ""
echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
unset DOCKER_PASS  # Clear password from memory

# Step 3: Create metadata file and write header
echo "Docker Backup Metadata - $(date)" > "$BACKUP_METADATA"
echo "----------------------------------" >> "$BACKUP_METADATA"

# Step 4: Process running containers
for container in $(docker ps -q); do
  container_name=$(docker inspect --format '{{.Name}}' $container | sed 's/\///g')
  timestamp=$(date +%Y%m%d%H%M%S)  # Unique timestamp
  image_name="$DOCKER_USER/${HOSTNAME}_${container_name}_backup:$timestamp"

  echo "Processing container: $container_name"
  echo "Container: $container_name" >> "$BACKUP_METADATA"
  echo "Image: $image_name" >> "$BACKUP_METADATA"
  echo "Timestamp: $timestamp" >> "$BACKUP_METADATA"

  # Step 5: Check if the container is a database and perform a dump
  container_image=$(docker inspect --format '{{.Config.Image}}' $container)
  db_backup_file=""
  inspect_file="~/docker_inspect_${container_name}.json"
  touch "${inspect_file}"
  if [[ "$container_image" == *"postgres"* ]]; then
    db_backup_file="/tmp/${HOSTNAME}_${container_name}_db_backup.sql"
    echo "Detected PostgreSQL container. Performing database dump..."
    docker exec $container pg_dumpall -U postgres > "$db_backup_file"
  elif [[ "$container_image" == *"mysql"* ]]; then
    db_backup_file="/tmp/${HOSTNAME}_${container_name}_db_backup.sql"
    echo "Detected MySQL container. Performing database dump..."
    docker exec $container mysqldump -u root --all-databases > "$db_backup_file"
  fi

  # Log database backup info
  if [[ -n "$db_backup_file" && -s "$db_backup_file" ]]; then
    echo "Database backup saved: $db_backup_file" >> "$BACKUP_METADATA"
  else
    echo "No database backup performed." >> "$BACKUP_METADATA"
  fi
  docker inspect "$container" > "$inspect_file"
  echo "Docker inspect data saved to $inspect_file" >> "$BACKUP_METADATA"
  # Step 6: Commit the container to a Docker image
  echo "Committing container $container_name as image $image_name..."
  docker commit $container "$image_name"

  # Step 7: Push the image to Docker Hub
  echo "Pushing image $image_name to Docker Hub..."
  docker push "$image_name"
  echo "Backup pushed: $image_name" >> "$BACKUP_METADATA"
  echo "----------------------------------" >> "$BACKUP_METADATA"
done

# Step 8: Track important system config files
CONFIG_FILES=(
  "/etc/php.ini" "/etc/php/*/php.ini"
  "/etc/my.cnf" "/etc/mysql/my.cnf"
  "/etc/nginx/nginx.conf"
  "/etc/httpd/conf/httpd.conf"
  "/etc/apache2/apache2.conf"
  "/etc/redis/redis.conf"
  "/etc/postgresql/*/postgresql.conf"
  "/etc/postgresql/*/pg_hba.conf"
)

echo "Tracking configuration files..." >> "$BACKUP_METADATA"
for file in "${CONFIG_FILES[@]}"; do
  if [[ -f $file ]]; then
    echo "Tracking $file" >> "$BACKUP_METADATA"
  fi
done

# Step 9: Logout from Docker Hub
echo "Logging out from Docker Hub..."
docker logout

echo "Backup complete! Metadata saved in $BACKUP_METADATA."


# Step 9: Add /home and track any non-default files in /
echo "Adding /home and tracking non-default files in /..."
git add /home
git add ~


# Step 10: Commit changes to Git
echo "Committing changes to Git..."
git commit -m "Backup Docker containers as images, web directories, and non-default files."

echo "Git repository setup complete!"

# Step 11: Log out from Docker Hub to ensure no session remains
echo "Logging out from Docker Hub..."

echo "Script execution complete. Docker session has been closed."

