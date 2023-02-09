declare namespace App.DataTransferObjects {
    export type MovieData = {
        id: number | null;
        title: any | string;
        originalTitle: any | string;
        allTitles: any | any | Array<any>;
        productCode: string;
        releaseDate: string | any | any;
        duration: any | any | number;
        studio: any | any | any;
        type: any;
        cast: any | any;
        createdAt: string | any | any;
        updatedAt: string | any | any;
        deletedAt: string | any | any;
    };
}
declare namespace App.Enums {
    export type MediaCollectionType =
        | 'front_cover'
        | 'full_cover'
        | 'profile'
        | 'logo';
    export type ReportType =
        | 'bad_image'
        | 'duplicate'
        | 'incorrect'
        | 'other'
        | 'spam';
}
