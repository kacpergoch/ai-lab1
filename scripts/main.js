class TodoApp {
	constructor() {
		this.storageKey = 'todo';
		this.tasks = [];
		this.term = '';

		this.ul = document.getElementById('tasks-ul');
		this.searchInput = document.getElementById('search-task');
		this.newTaskInput = document.getElementById('new-task-input');
		this.newDueInput = document.getElementById('due-date-input');
		this.addButton = document.getElementById('add-task-button');

		this._bindEvents();
		this._load();
		this.draw();
	}

	_bindEvents() {
		this.addButton.addEventListener('click', () => this.handleAdd());
		this.newTaskInput.addEventListener('keydown', (e) => {
			if (e.key === 'Enter') this.handleAdd();
		});

		this.searchInput.addEventListener('input', (e) => {
			this.term = e.target.value.trim();
			this.draw();
		});
	}

	_load() {
		const raw = localStorage.getItem(this.storageKey);
		if (raw) {
            const parsed = JSON.parse(raw);
			if (Array.isArray(parsed)) this.tasks = parsed;
		}
	}

	_save() {
		localStorage.setItem(this.storageKey, JSON.stringify(this.tasks));
	}

	_uid() {
		return Math.random().toString(36).slice(2, 9);
	}

	validateText(text) {
		if (typeof text !== 'string') return false;
		const len = text.trim().length;
		return len >= 3 && len <= 255;
	}

	escapeRegExp(string) {
		return String(string).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
	}

	handleAdd() {
		const text = this.newTaskInput.value.trim();
		const due = this.newDueInput.value || null;

		if (!this.validateText(text)) {
			alert('Task text must be between 3 and 255 characters');
			return;
		}

		const task = {
			id: this._uid(),
			text,
			dueDate: due,
			done: false,
		};

		this.tasks.push(task);
		this._save();
		this.newTaskInput.value = '';
		this.newDueInput.value = '';
		this.draw();
	}

	removeTask(id) {
		this.tasks = this.tasks.filter(t => t.id !== id);
		this._save();
		this.draw();
	}

	toggleDone(id, done) {
		const t = this.tasks.find(x => x.id === id);
		if (!t) return;
		t.done = !!done;
		this._save();
		this.draw();
	}

	updateTask(id, text, dueDate) {
		const t = this.tasks.find(x => x.id === id);
		if (!t) return false;
		if (!this.validateText(text)) return false;
		t.text = text;
		t.dueDate = dueDate || null;
		this._save();
		this.draw();
		return true;
	}

	get filteredTasks() {
		const term = this.term.trim().toLowerCase();
		if (term.length < 2) return this.tasks;
		return this.tasks.filter(t => t.text.toLowerCase().includes(term));
	}

	highlight(text, term) {
		if (!term) return text;
		const escaped = this.escapeRegExp(term);
		const re = new RegExp(`(${escaped})`, 'ig');
		return text.replace(re, '<mark>$1</mark>');
	}

	draw() {
		if (!this.ul) return;
	
		this.ul.innerHTML = '';

		const tasksToShow = this.filteredTasks;

		tasksToShow.forEach(task => {
			const li = document.createElement('li');
			li.dataset.id = task.id;

			const checkbox = document.createElement('input');
			checkbox.type = 'checkbox';
			checkbox.checked = !!task.done;
			checkbox.addEventListener('change', (e) => {
				this.toggleDone(task.id, e.target.checked);
			});

			const spanText = document.createElement('span');
			spanText.className = 'task-text';
			const term = this.term.trim();
			spanText.innerHTML = term.length >= 2 ? this.highlight(this._escapeHtml(task.text), term) : this._escapeHtml(task.text);

			const spanDue = document.createElement('span');
			spanDue.className = 'task-due';
			if (task.dueDate) {
				spanDue.textContent = ` (${task.dueDate})`;
			}

			spanDue.addEventListener('click', (e) => {
				e.stopPropagation();
				this._enterEditMode(task, li, true);
			});

			const del = document.createElement('button');
			del.className = 'delete-button';
			del.setAttribute('aria-label', 'Usuń');
			del.textContent = '🗑️';
			del.addEventListener('click', (e) => {
				e.stopPropagation();
				this.removeTask(task.id);
			});


			li.appendChild(checkbox);
			li.appendChild(spanText);
			li.appendChild(spanDue);
			li.appendChild(del);

			li.addEventListener('click', (e) => {
				if (e.target === del || e.target === checkbox) return;
				const focusDate = !!e.target.closest && !!e.target.closest('.task-due');
				this._enterEditMode(task, li, focusDate);
			});

			this.ul.appendChild(li);
		});
	}

	_enterEditMode(task, li, focusDate = false) {
		li.innerHTML = '';
		const checkbox = document.createElement('input');
		checkbox.type = 'checkbox';

		const textInput = document.createElement('input');
		textInput.type = 'text';
		textInput.value = task.text;
		textInput.className = 'edit-text';

		const dateInput = document.createElement('input');
		dateInput.type = 'date';
		dateInput.value = task.dueDate || '';
		dateInput.className = 'edit-date';

		const saveAndExit = () => {
			const newText = textInput.value.trim();
			const newDue = dateInput.value || null;
			if (!this.validateText(newText)) {
				alert('Task text must be between 3 and 255 characters');
				return;
			}
			this.updateTask(task.id, newText, newDue);
		};

		textInput.addEventListener('keydown', (e) => {
			if (e.key === 'Enter') { e.preventDefault(); saveAndExit(); }
			else if (e.key === 'Escape') { e.preventDefault(); this.draw(); }
		});

		[textInput, dateInput].forEach(el => {
			el.addEventListener('pointerdown', (ev) => {
				ev.stopPropagation();
			});
		});

		const conditionalSave = () => {
			setTimeout(() => {
				if (!li.contains(document.activeElement)) saveAndExit();
			}, 0);
		};

		textInput.addEventListener('blur', conditionalSave);
		dateInput.addEventListener('blur', conditionalSave);

		const del = document.createElement('button');
		del.className = 'delete-button';
		del.setAttribute('aria-label', 'Usuń');
		del.textContent = '🗑️';
		del.addEventListener('click', (e) => {
			e.stopPropagation();
			this.removeTask(task.id);
		});

		li.appendChild(checkbox);
		li.appendChild(textInput);
		li.appendChild(dateInput);
		li.appendChild(del);

	requestAnimationFrame(() => {
		if (focusDate) dateInput.focus();
		else textInput.focus();
	});
	}

	_escapeHtml(unsafe) {
		return unsafe
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}
}

document.addEventListener('DOMContentLoaded', () => {
	new TodoApp();
});
