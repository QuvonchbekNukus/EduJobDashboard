from aiogram.fsm.state import State, StatesGroup


class RegistrationRole(StatesGroup):
    choosing = State()


class EmployerForm(StatesGroup):
    org_name = State()
    org_type = State()
    region = State()
    city = State()
    district = State()
    address = State()
    org_contact = State()


class SeekerForm(StatesGroup):
    region = State()
    experience = State()
    salary_min = State()
    work_format = State()
    about_me = State()
    seeker_type = State()
    subject = State()
    cv_file = State()

