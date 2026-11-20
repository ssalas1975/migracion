# /plan-migration

**Command**: Starts the migration planning process.

**Workflow**:
1. Orchestrator invokes `planner` agent
2. Planner analyzes project scope, generates milestones
3. Repo-explorer maps `wp-content` structure
4. Initial checklist created in migration log

**Entry point**: Any agent can trigger this command
