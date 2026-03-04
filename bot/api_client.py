from __future__ import annotations

import asyncio
import json
import logging
from dataclasses import dataclass
from typing import Any

import httpx

from bot.config import Settings

logger = logging.getLogger(__name__)


class ApiError(Exception):
    """Base API error."""


class ApiUnauthorizedError(ApiError):
    """Raised on HTTP 401."""


@dataclass(slots=True)
class ApiValidationError(ApiError):
    errors: dict[str, Any]
    message: str = "Validation error"


class ApiConnectionError(ApiError):
    """Raised on timeout or transport failures after retries."""


@dataclass(slots=True)
class ApiResponseError(ApiError):
    status_code: int
    message: str
    payload: Any


class BackendApiClient:
    def __init__(self, settings: Settings) -> None:
        self.settings = settings
        self.client = httpx.AsyncClient(
            base_url=settings.api_base_url + "/",
            timeout=settings.api_timeout,
            headers={
                "Accept": "application/json",
                "X-BOT-TOKEN": settings.bot_api_token,
            },
        )

    async def close(self) -> None:
        await self.client.aclose()

    async def request(
        self,
        method: str,
        path: str,
        *,
        json_data: dict[str, Any] | None = None,
        params: dict[str, Any] | None = None,
    ) -> Any:
        retries = [1, 2]

        for attempt in range(len(retries) + 1):
            try:
                response = await self.client.request(
                    method=method,
                    url=path.lstrip("/"),
                    json=json_data,
                    params=params,
                )
            except (httpx.TimeoutException, httpx.TransportError) as exc:
                logger.exception("API request transport error (%s %s): %s", method, path, exc)
                if attempt < len(retries):
                    await asyncio.sleep(retries[attempt])
                    continue
                raise ApiConnectionError("Backend is unavailable") from exc

            payload = self._safe_json(response)

            if response.status_code == 401:
                logger.error("API unauthorized (%s %s): %s", method, path, payload)
                raise ApiUnauthorizedError("BOT_API_TOKEN noto'g'ri")

            if response.status_code == 422:
                errors = payload.get("errors", {}) if isinstance(payload, dict) else {}
                logger.error("API validation error (%s %s): %s", method, path, errors)
                raise ApiValidationError(errors=errors, message="Validation error")

            if response.status_code >= 400:
                message = self._extract_message(payload) or f"HTTP {response.status_code}"
                logger.error("API response error (%s %s): %s", method, path, payload)
                raise ApiResponseError(response.status_code, message, payload)

            return payload

        raise ApiConnectionError("Backend is unavailable")

    async def upsert_user(self, payload: dict[str, Any]) -> dict[str, Any]:
        response = await self.request("POST", self.settings.users_upsert_path, json_data=payload)
        return self._extract_object(response, "user")

    async def upsert_employer(self, payload: dict[str, Any]) -> dict[str, Any]:
        response = await self.request("POST", self.settings.employers_upsert_path, json_data=payload)
        return self._extract_object(response, "employer")

    async def upsert_seeker(self, payload: dict[str, Any]) -> dict[str, Any]:
        response = await self.request("POST", self.settings.seekers_upsert_path, json_data=payload)
        return self._extract_object(response, "seeker")

    async def list_regions(self) -> list[dict[str, Any]]:
        response = await self.request("GET", self.settings.regions_path)
        return self._extract_list(response, "regions")

    async def list_seekers_types(self) -> list[dict[str, Any]]:
        response = await self.request("GET", self.settings.seekers_types_path)
        return self._extract_list(response, "seekers_types")

    async def list_subjects(self) -> list[dict[str, Any]]:
        response = await self.request("GET", self.settings.subjects_path)
        return self._extract_list(response, "subjects")

    @staticmethod
    def _safe_json(response: httpx.Response) -> Any:
        if response.status_code == 204:
            return {}
        try:
            return response.json()
        except json.JSONDecodeError:
            return {"message": response.text}

    @staticmethod
    def _extract_message(payload: Any) -> str:
        if isinstance(payload, dict):
            message = payload.get("message")
            if isinstance(message, str):
                return message
        return ""

    @staticmethod
    def _extract_object(payload: Any, key: str) -> dict[str, Any]:
        if not isinstance(payload, dict):
            return {}

        if isinstance(payload.get(key), dict):
            return payload[key]

        data = payload.get("data")
        if isinstance(data, dict):
            return data

        return {}

    @staticmethod
    def _extract_list(payload: Any, key: str) -> list[dict[str, Any]]:
        if not isinstance(payload, dict):
            return []

        candidate = payload.get(key)
        if isinstance(candidate, list):
            return [item for item in candidate if isinstance(item, dict)]

        data = payload.get("data")
        if isinstance(data, list):
            return [item for item in data if isinstance(item, dict)]

        return []

