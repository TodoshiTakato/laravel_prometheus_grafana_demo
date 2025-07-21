import React from 'react';
import { Container, Typography, AppBar, Toolbar, Box } from '@mui/material';
import MetricsDisplay from './components/MetricsDisplay';

function App() {
  return (
    <div>
      <AppBar position="static">
        <Toolbar>
          <Typography variant="h6">
            Laravel Metrics Dashboard
          </Typography>
        </Toolbar>
      </AppBar>
      <Container maxWidth="lg" sx={{ mt: 4 }}>
        <Box sx={{ mb: 4 }}>
          <Typography variant="h4" component="h1" gutterBottom>
            Application Metrics
          </Typography>
          <Typography variant="body1" color="text.secondary">
            Real-time metrics from Laravel application
          </Typography>
        </Box>
        <MetricsDisplay />
      </Container>
    </div>
  );
}

export default App;
