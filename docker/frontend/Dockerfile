FROM node:20-alpine

WORKDIR /app

# Add healthcheck
HEALTHCHECK --interval=30s --timeout=3s --start-period=30s --retries=3 \
    CMD wget -q --spider http://localhost:3000 || exit 1

# Start development server with host 0.0.0.0 to allow external access
CMD ["sh", "-c", "npm install && npm start"] 