declare namespace App.DataTransferObjects {
    export type MovieData = {
        id: number | null;
        title: string;
        originalTitle: string;
        productCode: string;
        duration: number | null;
        cast: any;
        studio: any;
        type: any;
    };
}
declare namespace App.Enums {
    export type ReportType =
        | 'bad_image'
        | 'duplicate'
        | 'incorrect'
        | 'other'
        | 'spam';
}
