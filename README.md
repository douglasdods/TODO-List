## (1) DEVELOPING A NEW TOOL (Web service/API only. No user interface needed.)

### User Stories
**1) As a guest user, I want to see the items of my TO DO list, so that I can see what tasks I need to work on.**

**Metodh - List**
```
{
        "RequestType": GET, 
        "URL": /todolist/,
        "function": listTasks,
        "parameters": [],
}
```

**Metodh - Single**
```
{
        "RequestType":	 GET, 
        "URL":	 /todolist/{task_id},
        "function":	 singleTask,
        "parameters": [task_id]
}
```

****


**2) As a guest user, I want to add a new item into my TO DO list, so that I can store my tasks.**

**Metodh**
```
{
        "RequestType":	 POST, 
        "URL":	 /todolist/,
        "function":	 updateTask,
        "parameters": [ type, content]
}
```

****

**3) As a guest user, I want to delete a task from my TO DO list, so that I can discard the tasks that I will no longer need to do.**

**Metodh**
```
{
        "RequestType":	 DELETE, 
        "URL":	 /todolist/{task_id},
        "function":	 deleteTask,
        "parameters": [task_id]
}
```
****

**4) As a guest user, I want to prioritize the tasks of my TO DO list, so that I can organize my work and always deliver the most valuable things first.**

**Metodh**
```
{
        "RequestType":	 PUT, 
        "URL":	 /todolist/{task_id},
        "function":	 updateTask,
        "parameters": [task_id,type,content,sort_order,done]
}
```
****

**5) As a guest user, I want to prioritize the tasks of my TO DO list, so that I can organize my work and always deliver the most valuable things first.**

**Metodh**
```
{
        "RequestType":	 PUT, 
        "URL":	 /todolist/{task_id},
        "function":	 updateTask,
        "parameters": [task_id,type,content,sort_order,done]
}
```
### This is the same method as item 4. This method performs the sorting of the entire list.