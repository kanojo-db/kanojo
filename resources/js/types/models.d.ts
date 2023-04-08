export interface ContentReport {
    // columns
    id: number;
    reporter_id: number;
    reportable_id: number;
    reportable_type: string;
    type: string;
    message: string;
    public: boolean;
    resolved: boolean;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
    // relations
    reportable: ContentReport;
    reporter: User;
}
export type ContentReports = ContentReport[];

interface TranslatableString {
    [index: string]: string;
}

export interface Movie {
    // columns
    id: number;
    title: TranslatableString;
    product_code: string;
    release_date: Date | null;
    length: number | null;
    studio_id: number | null;
    type_id: number | null;
    created_at: Date | null;
    updated_at: Date | null;
    love_reactant_id: number | null;
    slug: string | null;
    deleted_at: Date | null;
    // mutators
    translations: unknown;
    // relations
    reports: ContentReports;
    studio: Studio;
    type: MovieType;
    models: People;
    audits: Audits;
    tags: Tags;
    tags_translated: Tags;
    media: Media;
    love_reactant: Reactant;
}
export type Movies = Movie[];

export interface MovieType {
    // columns
    id: number;
    name: string;
    created_at: Date | null;
    updated_at: Date | null;
    // relations
    movies: Movies;
}
export type MovieTypes = MovieType[];

export interface Person {
    // columns
    id: number;
    name: TranslatableString;
    birthdate: Date | null;
    career_start: Date | null;
    career_end: Date | null;
    height: boolean | null;
    bust: boolean | null;
    waist: boolean | null;
    hip: boolean | null;
    blood_type: string | null;
    cup_size: string | null;
    breast_implants: boolean | null;
    country: string | null;
    created_at: Date | null;
    updated_at: Date | null;
    dob_doubt: boolean;
    deleted_at: Date | null;
    // mutators
    translations: unknown;
    // relations
    reports: ContentReports;
    movies: Movies;
    audits: Audits;
    media: Media;
}
export type People = Person[];

export interface Studio {
    // columns
    id: number;
    name: TranslatableString;
    founded_date: Date | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
    // mutators
    translations: unknown;
    // relations
    movies: Movies;
    audits: Audits;
    media: Media;
}
export type Studios = Studio[];

export interface User {
    // columns
    id: number;
    name: string;
    email: string;
    email_verified_at: Date | null;
    password?: string;
    two_factor_secret?: string | null;
    two_factor_recovery_codes?: string | null;
    two_factor_confirmed_at: Date | null;
    remember_token?: string | null;
    current_team_id: number | null;
    profile_photo_path: string | null;
    created_at: Date | null;
    updated_at: Date | null;
    love_reacter_id: number | null;
    deleted_at: Date | null;
    // relations
    favorites: Movies;
    wishlist: Movies;
    collection: Movies;
    audits: Audits;
    tokens: PersonalAccessTokens;
    notifications: DatabaseNotifications;
    love_reacter: Reacter;
    roles: UserRoles;
    permissions: Permissions;
}
export type Users = User[];

export interface UserRole {
    name: string;
    created_at: string;
    guard_name: string;
    id: number;
    pivot: {
        model_id: number;
        model_type: string;
        role_id: number;
    };
    updated_at: string;
}
export type UserRoles = UserRole[];
