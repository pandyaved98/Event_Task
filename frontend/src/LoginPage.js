import React from 'react';
import { GoogleLogin, GoogleOAuthProvider } from '@react-oauth/google';
import './LoginPage.css'; // Create this CSS file for styling

const LoginPage = () => {
  const responseMessage = (response) => {
    console.log("Success Response:", response);
    // Handle the response or navigate to the dashboard upon successful login
  };

  const errorMessage = (error) => {
    console.error('Error:', error);
    // Handle the error if needed
  };

  return (
    <div className="login-page">
      <h1 className="evento-title">EventO!</h1>
      <div className="login-container">
        <GoogleOAuthProvider clientId=''>
          <GoogleLogin onSuccess={responseMessage} onError={errorMessage} />
        </GoogleOAuthProvider>
      </div>
    </div>
  );
};

export default LoginPage;