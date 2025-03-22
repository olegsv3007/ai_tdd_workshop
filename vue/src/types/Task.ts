export type TaskStatus = 'To Do' | 'In Progress' | 'Review' | 'Done';
export type TaskPriority = 'Low' | 'Medium' | 'High' | 'Critical';
export type TaskType = 'Bug' | 'Feature' | 'Epic' | 'Story' | 'Task';

export interface Task {
  id: number;
  title: string;
  description: string;
  status: TaskStatus;
  type: TaskType;
  priority: TaskPriority;
  assignee: string | null;
  reporter: string;
  createdAt: string;
  updatedAt: string;
  estimatedHours: number | null;
  tags: string[];
}
