import axios from "axios";
import { Platform } from "react-native";

export const MOCK_MODE = false;

// Your exact computer LAN IP - 100% verified
const BASE_LAN_IP = "192.168.1.118";
const PORT = "8000";
export const API_PREFIX = "/api/v1";

// Renders strictly to: http://192.168.1.118:8000/api/v1
export const WEB_BASE_URL = `http://${BASE_LAN_IP}:${PORT}${API_PREFIX}`;
export const NATIVE_BASE_URL = `http://${BASE_LAN_IP}:${PORT}${API_PREFIX}`;

const getBaseUrl = () => {
    return Platform.OS === "web" ? WEB_BASE_URL : NATIVE_BASE_URL;
};

// Axios Client instance
export const apiClient = axios.create({
    baseURL: getBaseUrl(),
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

export interface ApiUser {
    id: number;
    name: string;
    email: string;
    is_admin: boolean;
    is_approved: boolean;
}

export interface LaravelLoginEnvelope {
    status: string;
    data: {
        token: string;
        user: ApiUser;
    };
}

// Fixed login fetch configuration with proper routing slashes
export const apiLogin = async (
    email: string,
    password: string,
): Promise<{ token: string; user: ApiUser }> => {
    // FIXED: Added the missing '/' between baseUrl and auth/login
    const response = await fetch(`${getBaseUrl()}/auth/login`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify({
            email,
            password,
            device_name:
                Platform.OS === "web"
                    ? "Chrome Browser"
                    : `${Platform.OS} Device`,
        }),
    });

    if (!response.ok) {
        const errorData = await response.json().catch(() => ({}));
        throw new Error(errorData.message || "Login failed.");
    }

    const envelope: LaravelLoginEnvelope = await response.json();
    return {
        token: envelope.data.token,
        user: envelope.data.user,
    };
};
