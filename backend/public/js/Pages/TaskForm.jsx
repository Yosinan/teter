import { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

const TaskForm = () => {
    const [title, setTitle] = useState("");
    const [description, setDescription] = useState("");
    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            await axios.post("/api/tasks", { title, description });
            navigate("/tasks");
        } catch (error) {
            console.error("Task creation failed", error);
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            <input type="text" value={title} onChange={(e) => setTitle(e.target.value)} placeholder="Title" required />
            <textarea value={description} onChange={(e) => setDescription(e.target.value)} placeholder="Description" />
            <button type="submit">Create Task</button>
        </form>
    );
};

export default TaskForm;