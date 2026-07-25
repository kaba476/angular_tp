import { Component, inject, OnInit, signal } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Task } from '../../core/models/task.model';
import { TaskService } from '../../core/services/task.service';

@Component({
  selector: 'app-tasks',
  imports: [FormsModule],
  templateUrl: './tasks.component.html',
  styleUrl: './tasks.component.css',
})
export class TasksComponent implements OnInit {
  private taskService = inject(TaskService);

  tasks = signal<Task[]>([]);
  form = { title: '', description: '', is_done: false };
  editingId: number | null = null;

  ngOnInit() {
    this.load();
  }

  load() {
    this.taskService.getTasks().subscribe((tasks) => this.tasks.set(tasks));
  }

  submit() {
    const data = { ...this.form };

    if (this.editingId) {
      this.taskService.update(this.editingId, data).subscribe(() => {
        this.load();
        this.reset();
      });
    } else {
      this.taskService.create(data).subscribe(() => {
        this.load();
        this.reset();
      });
    }
  }

  edit(task: Task) {
    this.editingId = task.id;
    this.form = {
      title: task.title,
      description: task.description || '',
      is_done: task.is_done,
    };
  }

  reset() {
    this.editingId = null;
    this.form = { title: '', description: '', is_done: false };
  }

  toggle(task: Task) {
    this.taskService.update(task.id, { is_done: !task.is_done }).subscribe(() => this.load());
  }

  remove(id: number) {
    this.taskService.delete(id).subscribe(() => this.load());
  }
}
