FROM nginx:alpine

# Install curl for healthcheck
RUN apk add --no-cache curl

# Copy the nginx config file
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Add healthcheck
HEALTHCHECK --interval=30s --timeout=3s --start-period=30s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Start nginx in foreground mode
CMD ["nginx", "-g", "daemon off;"] 