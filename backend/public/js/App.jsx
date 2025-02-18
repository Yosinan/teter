import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";
import { useState, useEffect } from "react";
import axios from "axios";
import Login from "./Pages/Login";
import Register from "./Pages/Register";
import TaskList from "./Pages/TaskList";
import TaskForm from "./TaskForm";
// import TaskItem from "../js/Components/TaskItem";
import Navbar from "./Components/Navbar"

const App = () => {
  const [user, setUser] = useState(null);
  
  useEffect(() => {
    const token = localStorage.getItem("token");
    if (token) {
      axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
      axios.get("/api/user").then((response) => setUser(response.data)).catch(() => setUser(null));
    }
  }, []);

  const logout = () => {
    localStorage.removeItem("token");
    setUser(null);
    delete axios.defaults.headers.common["Authorization"];
  };

  return (
    <Router>
      <Navbar user={user} logout={logout} />
      <Routes>
        <Route path="/login" element={user ? <Navigate to="/tasks" /> : <Login setUser={setUser} />} />
        <Route path="/register" element={user ? <Navigate to="/tasks" /> : <Register />} />
        <Route path="/tasks" element={user ? <TaskList user={user} /> : <Navigate to="/login" />} />
        <Route path="/tasks/new" element={user ? <TaskForm /> : <Navigate to="/login" />} />
      </Routes>
    </Router>
  );
};

export default App;