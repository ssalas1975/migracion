# meytel-orchestrator

**Purpose**: Workflow coordination between all migration agents.

**Key Responsibilities**:
- Sequence agent execution (planner → explorer → auditor → tester)
- Aggregate findings across agents
- Resolve conflicts between recommendations
- Maintain overall migration state
- Coordinate with external systems (Staging, Production)

**Workflow pattern**:
1. `planner` → scope & milestones
2. `explorer` → map structure
3. `auditor-php` → compatibility scan
4. `security` → security audit
5. `tester` → functional verification
6. `documenter` → log changes

**Entry point**: Root directory (or called by any agent)
