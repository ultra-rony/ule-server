REMOTE_USER=root
REMOTE_HOST=194.5.78.208
REMOTE_DIR=/root/ule-server-ci3

deploy:
	rsync -avz --delete \
		--exclude '.git/' \
		--exclude 'node_modules/' \
		--exclude 'vendor/' \
		--exclude 'uploads/' \
		./ $(REMOTE_USER)@$(REMOTE_HOST):$(REMOTE_DIR)
