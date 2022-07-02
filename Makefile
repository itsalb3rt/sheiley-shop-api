## Install dependencies
install-dependencies:
	@docker run --rm -v "${PWD}:/app" -w /app composer:1.10.12 install