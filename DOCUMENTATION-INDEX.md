# MKG-CMS Documentation Index

**Project:** Sign Shop CMS (mkg-cms)  
**Version:** 1.0.0  
**Last Updated:** 2026-06-02

---

## Documentation Overview

This project includes comprehensive documentation covering all aspects of the CMS system. This index helps you find the right document for your needs.

---

## Core Documentation

### 1. **CLAUDE.md** – Claude Project Guide
**Purpose:** Primary reference for AI assistant (Claude) when working on this project  
**Audience:** Claude, developers using AI assistance  
**Contents:**
- Project overview and architecture
- Directory structure
- Core components (Router, Database, Auth, CSRF)
- Database schema reference
- Request flow diagrams
- Security practices
- Development patterns (CRUD, validation, soft delete)
- Common tasks and configuration
- Testing checklist
- Code style conventions
- Deployment notes

**When to use:** Starting work on the project, understanding system architecture, implementing new features

---

### 2. **FRONTEND-FLOW.md** – Frontend and UX-UI Pro Flow
**Purpose:** Complete user experience and data flow documentation  
**Audience:** Frontend developers, UX designers, full-stack developers  
**Contents:**
- Public website user flows (home, products, portfolio, contact)
- Admin panel flows (login, dashboard, CRUD operations)
- Detailed request-response cycles with diagrams
- CLI commands reference
- API route summary
- JavaScript components (media picker, slug generator, menu reordering)
- Data flow diagrams
- Security flow patterns (CSRF, file upload)
- Complete user journey examples
- Performance optimization patterns
- Accessibility features

**When to use:** Understanding user interactions, implementing frontend features, debugging flows

---

### 3. **api.md** – API and Route Contract
**Purpose:** Authoritative HTTP route and form field specification  
**Audience:** Backend developers, API consumers  
**Contents:**
- Global rules (authentication, CSRF, PRG pattern)
- Public routes specification
- Admin authentication routes
- Admin CRUD routes (pages, products, portfolios, media, menus, settings)
- Form field specifications with validation rules
- Error handling standards
- Query parameters and pagination

**When to use:** Implementing routes, validating forms, understanding API contracts

---

### 4. **SRS.md** – Software Requirements Specification
**Purpose:** Business requirements and functional specifications  
**Audience:** Project managers, stakeholders, developers  
**Contents:**
- Business objectives
- Functional requirements
- User roles and permissions
- Feature specifications
- Non-functional requirements
- Acceptance criteria

**When to use:** Understanding project scope, clarifying requirements, planning features

---

### 5. **TSD.md** – Technical System Design
**Purpose:** Detailed technical architecture and design decisions  
**Audience:** System architects, senior developers  
**Contents:**
- System architecture diagrams
- Technology stack decisions
- Database design rationale
- Component interaction patterns
- Scalability considerations
- Security architecture

**When to use:** Making architectural decisions, understanding system design, refactoring

---

## Supporting Documentation

### 6. **SECURITY.md** – Security Requirements
**Purpose:** Security guidelines and checklist  
**Audience:** Security engineers, developers  
**Contents:**
- Authentication security
- Input validation rules
- Output escaping requirements
- File upload security
- CSRF protection
- SQL injection prevention
- Session security
- Production security checklist

**When to use:** Implementing security features, security audits, penetration testing prep

---

### 7. **tasks.md** – Implementation Tasks
**Purpose:** Project task tracking and completion status  
**Audience:** Project managers, developers  
**Contents:**
- Task milestones (M1-M7)
- Foundation tasks (DONE)
- Security and auth tasks (DONE)
- Admin content tasks (DONE)
- Media tasks (DONE)
- Product tasks (DONE)
- Portfolio tasks (DONE)
- Frontend tasks (mostly DONE)
- Quality gate checklist

**When to use:** Tracking progress, planning sprints, identifying remaining work

---

### 8. **architecture.md** – Architecture Documentation
**Purpose:** High-level architecture overview  
**Audience:** All developers  
**Contents:**
- MVC pattern explanation
- Layer responsibilities
- Component relationships
- Design patterns used

**When to use:** Onboarding new developers, understanding code organization

---

### 9. **testing.md** – Test Plans and Scenarios
**Purpose:** Testing strategy and test cases  
**Audience:** QA engineers, developers  
**Contents:**
- Test scenarios
- Edge cases
- Integration test plans
- Manual testing procedures

**When to use:** Writing tests, QA testing, regression testing

---

### 10. **context.md** – Project Context
**Purpose:** Background information and project history  
**Audience:** New team members, stakeholders  
**Contents:**
- Project background
- Business context
- Key decisions and rationale

**When to use:** Understanding why decisions were made, onboarding

---

### 11. **README.md** – Quick Start Guide
**Purpose:** Quick project introduction  
**Audience:** Everyone  
**Contents:**
- Project summary
- Quick start instructions
- Basic setup
- Documentation links

**When to use:** First time accessing the project

---

## Documentation Quick Reference

### For Different Roles

**New Developer:**
1. Start with README.md
2. Read CLAUDE.md (sections 1-3)
3. Review architecture.md
4. Study FRONTEND-FLOW.md for user flows

**Frontend Developer:**
1. FRONTEND-FLOW.md (complete)
2. api.md (public routes)
3. CLAUDE.md (section 8: Common Tasks)

**Backend Developer:**
1. CLAUDE.md (complete)
2. api.md (complete)
3. TSD.md (database design)
4. SECURITY.md

**Full-Stack Developer:**
1. CLAUDE.md (complete)
2. FRONTEND-FLOW.md (complete)
3. api.md (complete)

**DevOps Engineer:**
1. CLAUDE.md (deployment section)
2. SECURITY.md
3. TSD.md (infrastructure)

**QA Engineer:**
1. testing.md
2. FRONTEND-FLOW.md (user journeys)
3. api.md (validation rules)
4. SECURITY.md (security checklist)

**Project Manager:**
1. README.md
2. SRS.md
3. tasks.md
4. context.md

---

## Common Tasks → Documentation Mapping

| Task | Primary Doc | Secondary Doc |
|---|---|---|
| Add new route | api.md | CLAUDE.md (dev patterns) |
| Create CRUD feature | CLAUDE.md (section 8) | FRONTEND-FLOW.md |
| Fix security issue | SECURITY.md | CLAUDE.md (security) |
| Understand user flow | FRONTEND-FLOW.md | api.md |
| Set up project | README.md | CLAUDE.md (common tasks) |
| Deploy to production | CLAUDE.md (deployment) | SECURITY.md |
| Write tests | testing.md | FRONTEND-FLOW.md |
| Understand database | CLAUDE.md (schema) | TSD.md |
| Debug request flow | FRONTEND-FLOW.md (section 4) | api.md |
| Implement media picker | FRONTEND-FLOW.md (section 6.1) | CLAUDE.md |
| Configure settings | CLAUDE.md (configuration) | api.md |

---

## Documentation Standards

All documentation in this project follows these standards:

1. **Markdown Format:** All docs use GitHub-flavored Markdown
2. **Version Headers:** Each document includes version and date
3. **Status Indicators:** Current/Draft/Deprecated clearly marked
4. **Cross-References:** Documents link to related docs
5. **Code Examples:** Include practical, working examples
6. **Diagrams:** Use ASCII/text-based diagrams for clarity
7. **Update Policy:** Update docs with code changes, not after

---

## Getting Started Checklist

For new team members:

- [ ] Read README.md
- [ ] Review CLAUDE.md sections 1-2 (Overview, Architecture)
- [ ] Scan FRONTEND-FLOW.md section 1 (Overview)
- [ ] Review api.md section 2 (Global Rules)
- [ ] Read SECURITY.md
- [ ] Set up local development environment
- [ ] Run through at least one complete user flow
- [ ] Implement a small feature to understand patterns

---

## Contributing to Documentation

When updating documentation:

1. **Keep CLAUDE.md current** – It's the main reference
2. **Update FRONTEND-FLOW.md** when changing user flows
3. **Update api.md** when routes or fields change
4. **Update tasks.md** when completing tasks
5. **Mark outdated info** with strikethrough or deprecation notice
6. **Add date stamps** when making significant changes
7. **Cross-link related changes** across documents

---

## Documentation Maintenance

| Document | Update Frequency | Owner |
|---|---|---|
| CLAUDE.md | On architecture changes | Tech Lead |
| FRONTEND-FLOW.md | On UI/flow changes | Frontend Lead |
| api.md | On route/field changes | Backend Lead |
| SECURITY.md | On security changes | Security Lead |
| tasks.md | Daily/weekly | Project Manager |
| SRS.md | On requirement changes | Product Owner |
| TSD.md | On design changes | Architect |
| README.md | On major changes | Tech Lead |

---

*This index is maintained as the central guide to all project documentation.*
