# /verify-staging

**Command**: Runs functional tests on Staging environment before Production deployment.

**Workflow**:
1. Orchestrator invokes `tester` agent
2. Tester executes predefined test scenarios (Checkout, Forms, etc.)
3. Validates data integrity and functionality
4. Reports any regressions or issues

**Prerequisites**: Staging environment connection credentials (stored in agent config)

**Entry point**: Any agent or direct user command
