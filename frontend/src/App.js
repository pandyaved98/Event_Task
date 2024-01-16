import React, { useState } from 'react';
import { GoogleLogin, GoogleOAuthProvider } from '@react-oauth/google';
import axios from 'axios';
import { Route, Routes, useNavigate } from 'react-router-dom';
import Dashboard from './Dashboard';
import './App.css';

const LoginPage = () => {
  const [user, setUser] = useState(null);
  const navigate = useNavigate();

  const responseMessage = async (response) => {
    console.log("Success Response:", response);

    try {
      const userResponse = await axios.get('https://www.googleapis.com/oauth2/v3/userinfo.profile', {
        headers: {
          Authorization: `Bearer ${response.credential}`,
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'Access-Control-Allow-Origin': '*',
        },
      });

      // Send user data to backend
      await axios.post(
        'http://localhost/allevent_task/backend/apis/processUser.php', // Replace with your actual path
        {
          name: userResponse.data.name,
          email: userResponse.data.email,
          profilePhoto: userResponse.data.picture,
        }
      );

      // Store user details in state
      setUser({
        name: userResponse.data.name,
        email: userResponse.data.email,
        profilePicture: userResponse.data.picture,
      });

      // Redirect to the dashboard after successful login
      navigate('/dashboard');

    } catch (error) {
      console.error('Error storing user data:', error);
    }
  };

  const errorMessage = (error) => {
    console.log(error);
  };

  return (
    <div>
      <h1>EventO!</h1>
      {/* <div className="button-container">
        <GoogleOAuthProvider clientId=''>
          <GoogleLogin onSuccess={responseMessage} onError={errorMessage} />
        </GoogleOAuthProvider>
      </div>
      {user && <Dashboard />} */}
        <Routes>
          <Route path="/" element={<LoginPage />} />
          <Route path="/dashboard" element={<Dashboard />} />
        </Routes>
    </div>
  );
};

export default LoginPage;