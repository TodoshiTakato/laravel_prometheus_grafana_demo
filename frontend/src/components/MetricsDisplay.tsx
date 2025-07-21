import React, { useEffect, useState } from 'react';
import { Card, CardContent, Typography, Grid } from '@mui/material';
import axios from 'axios';

interface Metric {
    name: string;
    value: number;
    description: string;
}

const MetricsDisplay: React.FC = () => {
    const [metrics, setMetrics] = useState<Metric[]>([]);

    const fetchMetrics = async () => {
        try {
            const response = await axios.get('http://localhost/metrics');
            const metricsText = response.data;
            
            // Parse Prometheus metrics format
            const parsedMetrics: Metric[] = [];
            const lines = metricsText.split('\n');
            
            let currentMetric: Partial<Metric> = {};
            
            lines.forEach(line => {
                if (line.startsWith('# HELP')) {
                    const [, name, description] = line.match(/# HELP (\w+) (.+)/) || [];
                    currentMetric = { name, description };
                } else if (line.match(/^\w/)) {
                    const [name, value] = line.split(' ');
                    if (currentMetric.name === name) {
                        parsedMetrics.push({
                            name: currentMetric.name!,
                            description: currentMetric.description!,
                            value: parseFloat(value)
                        });
                    }
                }
            });
            
            setMetrics(parsedMetrics);
        } catch (error) {
            console.error('Error fetching metrics:', error);
        }
    };

    useEffect(() => {
        fetchMetrics();
        const interval = setInterval(fetchMetrics, 5000); // Update every 5 seconds
        return () => clearInterval(interval);
    }, []);

    return (
        <Grid container spacing={3}>
            {metrics.map((metric) => (
                <Grid item xs={12} sm={6} md={4} key={metric.name}>
                    <Card>
                        <CardContent>
                            <Typography variant="h6" component="div">
                                {metric.name}
                            </Typography>
                            <Typography variant="body2" color="text.secondary">
                                {metric.description}
                            </Typography>
                            <Typography variant="h4" component="div" sx={{ mt: 2 }}>
                                {metric.value.toFixed(2)}
                            </Typography>
                        </CardContent>
                    </Card>
                </Grid>
            ))}
        </Grid>
    );
};

export default MetricsDisplay; 