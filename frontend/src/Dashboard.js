// Dashboard.js

import React, { useState, useEffect } from 'react';
import axios from 'axios';

const Dashboard = () => {
  const [events, setEvents] = useState([]);

  useEffect(() => {
    // Fetch events from the backend API
    const fetchEvents = async () => {
      try {
        const response = await axios.get('http://localhost/allevent_task/backend/apis/getEvents.php');
        setEvents(response.data.events);
      } catch (error) {
        console.error('Error fetching events:', error);
      }
    };

    fetchEvents();
  }, []);

  return (
    <div>
      <h2>Dashboard</h2>
      <div className="events-container">
        {events.map((event) => (
          <EventCard key={event.id} event={event} />
        ))}
      </div>
    </div>
  );
};

const EventCard = ({ event }) => {
  const { name, startDate, endDate, location, category, banner_image_url } = event;

  return (
    <div className="event-card">
      <img src={banner_image_url} alt={name} />
      <h3>{name}</h3>
      <p>{`Start Date: ${startDate}`}</p>
      <p>{`End Date: ${endDate}`}</p>
      <p>{`Location: ${location}`}</p>
      <p>{`Category: ${category}`}</p>
    </div>
  );
};

const handleLogout = () => {
  // Remove user data from local storage
  localStorage.removeItem('user');

  // Redirect to the login page
  window.location.href = '/';
};

export default Dashboard;
