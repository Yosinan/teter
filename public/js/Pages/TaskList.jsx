import { useEffect, useState } from "react";
import axios from "axios";
import TaskItem from "./TaskItem";

const TaskList = ({ user }) => {
    const [tasks, setTasks] = useState([]);

    useEffect(() => {
        axios.get("/api/tasks").then((response) => setTasks(response.data));
    }, []);

    return (
        <div>
            <h2>Tasks</h2>
            {tasks.map((task) => (
                <TaskItem key={task.id} task={task} user={user} />
            ))}
        </div>
    );
};

export default TaskList;