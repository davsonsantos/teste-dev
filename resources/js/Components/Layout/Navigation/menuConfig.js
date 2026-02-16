// menuConfig.js
import {
    BookA,
    BookOpen,
    CirclePlus,
    LayoutDashboard,
    Users
} from "lucide-vue-next";

export const menuItems = [
    { name: "Dashboard", route: "dashboard", icon: LayoutDashboard },
    {
        name: "Matriculas",
        route: "enrollments.index",
        icon: CirclePlus,
    },
    {
        name: "Cursos",
        route: "course.index",
        icon: BookA,
    },
    {
        name: "Disciplicas",
        route: "subject.index",
        icon: BookOpen,
    },
    {
        name: "Usuários",
        route: "users.index",
        icon: Users,
    },
];