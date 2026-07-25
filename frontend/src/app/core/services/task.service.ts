import { inject, Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map } from 'rxjs';
import { Task } from '../models/task.model';

type TaskCollectionResponse = Task[] | { data: Task[] };
type TaskResponse = Task | { data: Task };

@Injectable({
  providedIn: 'root',
})
export class TaskService {
  private http = inject(HttpClient);

  getTasks() {
    return this.http.get<TaskCollectionResponse>('/api/tasks').pipe(
      map((response) => Array.isArray(response) ? response : response.data)
    );
  }

  create(task: Partial<Task>) {
    return this.http.post<TaskResponse>('/api/tasks', task).pipe(
      map((response) => 'data' in response ? response.data : response)
    );
  }

  update(id: number, task: Partial<Task>) {
    return this.http.put<TaskResponse>(`/api/tasks/${id}`, task).pipe(
      map((response) => 'data' in response ? response.data : response)
    );
  }

  delete(id: number) {
    return this.http.delete<void>(`/api/tasks/${id}`);
  }
}
